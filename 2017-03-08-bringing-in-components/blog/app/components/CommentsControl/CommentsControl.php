<?php

declare(strict_types=1);

namespace App\Components\CommentsControl;

use App\Components\CommentDetailControl\CommentDetailControl;
use App\Components\CommentDetailControl\ICommentDetailControlFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\ArrayHash;

class CommentsControl extends Control
{

	/**
	 * @var callable[]
	 */
	public $onCommentAdded = [];

	/**
	 * @var ActiveRow
	 */
	private $article;

	/**
	 * @var Context
	 */
	private $db;

	/**
	 * @var ICommentDetailControlFactory
	 */
	private $commentDetailControlFactory;


	public function __construct(ActiveRow $article, Context $db, ICommentDetailControlFactory $commentDetailControlFactory)
	{
		$this->article = $article;
		$this->db = $db;
		$this->commentDetailControlFactory = $commentDetailControlFactory;
	}


	public function render(): void
	{
		$this->getTemplate()->article = $this->article;

		$this->getTemplate()->render(__DIR__ . '/templates/commentsControl.latte');
	}


	public function createComponentCommentForm(): Form
	{
		$form = new Form;

		$form->addHidden('article_id', $this->article->id);
		$form->addText('name', 'Name')->setRequired();
		$form->addTextarea('text', 'Comment text')->setRequired();
		$form->addSubmit('submit', 'Send');

		$form->onSuccess[] = function(Form $form, ArrayHash $values): void {
			$this->db->table('comment')->insert([
				'article_id' => $values->article_id,
				'name' => $values->name,
				'text' => $values->text,
				'date' => new \DateTime
			]);

			$this->flashMessage('Article added, thank you!');

			$this->onCommentAdded();
		};

		return $form;
	}


	public function createComponentCommentDetail(): CommentDetailControl
	{
		return $this->commentDetailControlFactory->create();
	}

}
