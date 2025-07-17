<?php
namespace Api\Controllers;

use Api\Models\Store;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Exception;
use Twig\Extension\DebugExtension;

class StoreController {
    private Environment $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../../app/Views');

        $this->twig = new Environment($loader, [
            'debug' => true
        ]);

        $this->twig->addExtension(new DebugExtension());
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $html = $this->twig->render('store/create.twig');
        $response->getBody()->write($html);
        return $response;
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $data = $request->getParsedBody();

            // Sanitização e validação
            $nome = htmlspecialchars(trim($data['nome'] ?? ''), ENT_QUOTES, 'UTF-8');
            $tipo = "COMIDA";

            if (!$nome) {
                $response->getBody()->write('Dados inválidos');
                return $response->withStatus(400);
            }

            $storeModel = new Store();

            $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');

            if ($storeModel->existeLoja($nome, $tipo, $_SESSION['usuario'])) {
                throw new Exception("Já existe uma loja com o mesmo nome e tipo. Tente novamente.");
            }

            $success = $storeModel->create([
                'nome'           => $nome,
                'tipo'           => $tipo,
                'idProprietario' => $_SESSION['usuario']
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

    public function dashboard(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        if (empty($_SESSION['usuario'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $storeModel = new Store();
        $todasLojas = $storeModel->obterComRestricoes(array("idProprietario" => $_SESSION['usuario']));

        $html = $this->twig->render('auth/dashboard.twig', ['todasLojas' => $todasLojas]);
        $response->getBody()->write($html);
        return $response->withStatus(302);
    }
}
