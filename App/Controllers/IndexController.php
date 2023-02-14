<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->usuario = array( // coloca dados vazios no forms
			'nome' => '',
			'email' => '',
			'senha' => '',
		);
		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar() {

		// receber os dados do formulário
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', $_POST['senha']);

		if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) { // se cadastro for válido e n houver nenhum registro no bd
			
			$usuario->salvar();
			$this->render('cadastro');

		} else {

			$this->view->usuario = array( // recarrega as info no forms
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha'],
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}
	}

}


?>