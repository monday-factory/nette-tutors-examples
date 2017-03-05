<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\ArrayHash;

class HomepagePresenter extends Presenter
{

	/**
	 * @var Context
	 */
	private $db;

	/**
	 * @var ActiveRow|NULL
	 */
	private $article;


	public function __construct(Context $db)
	{
		$this->db = $db;
	}


	public function renderDefault(): void
	{
		$this->getTemplate()->articles = $this->db->table('article')->order('id DESC');
	}


	public function actionDetail(int $id): void
	{
		$this->article = $this->db->table('article')->get($id);

		if (!$this->article) { // Article was not found
			throw new ForbiddenRequestException;
		}
	}


	public function renderDetail(): void
	{
		$this->getTemplate()->article = $this->article;
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
			$this->redirect('this');
		};

		return $form;
	}

}
