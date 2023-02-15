<?php 
namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline() {

        $this->validaAutenticacao();
        
        // recuperação dos tweets
        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->listar();

        $this->view->tweets = $tweets;

        $this->render('timeline');

    }

    public function tweet() {

        $this->validaAutenticacao();
           
        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweet->salvar();

        header('location: /timeline');

    }

    public function removerTweet() {

        $this->validaAutenticacao();

        $id = isset($_GET['id']) ? $_GET['id'] : '';
           
        $tweet = Container::getModel('Tweet');

        $tweet->__set('id', $id);

        $tweet->excluir();

        header('location: /timeline');

    }

    public function validaAutenticacao() {

        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
            header('location: /?login=erro');
        }
    }

    public function quemSeguir() {

        $this->validaAutenticacao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        $usuarios = array();

        if($pesquisarPor != '') {
            
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();

        }
        
        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
    }

    public function acao() {

        $this->validaAutenticacao();

        // qual acao
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        // id_usuario a ser seguido
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
       
        $usuario = Container::getModel('Usuario'); // Recupera a classe usuario
        // id_usuario da sessão
        $usuario->__set('id', $_SESSION['id']);

        if ($acao == 'seguir') {
            $usuario->seguirUsuario($id_usuario_seguindo);

        } else if ($acao == 'deixar_de_seguir') {
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('location: /quem_seguir');
        
    }
}

?>
   