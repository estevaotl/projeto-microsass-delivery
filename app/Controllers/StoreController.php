<?php
namespace App\Controllers;

use App\Models\Store;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Enums\StoreType;
use Exception;

class StoreController {
    private Environment $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $tiposLoja = StoreType::cases(); // retorna array de objetos StoreType

        $html = $this->twig->render('store/create.twig', [
            'tipos' => $tiposLoja
        ]);
        $response->getBody()->write($html);
        return $response;
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $data = $request->getParsedBody();

            // Sanitização e validação
            $nome = htmlspecialchars(trim($data['nome'] ?? ''), ENT_QUOTES, 'UTF-8');
            $tipo = $data['tipo'] ?? '';

            if (!$nome || !StoreType::tryFrom($tipo)) {
                $response->getBody()->write('Dados inválidos');
                return $response->withStatus(400);
            }

            $storeModel = new Store();

            $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');

            if ($storeModel->existeLoja($nome, $tipo)) {
                throw new Exception("Já existe uma loja com o mesmo nome e tipo. Tente novamente.");
            }

            $success = $storeModel->create([
                'nome' => $nome,
                'tipo' => $tipo
            ]);

            if (!$success) {
                throw new Exception("Erro ao salvar a loja.");
            }

            return $response
                ->withHeader('Location', '/loja/nova')
                ->withStatus(302);
        } catch (\Throwable $th) {
            $response->getBody()->write('Erro ao salvar a loja.' . $th->getMessage());
            return $response->withStatus(500);
        }
    }
}
