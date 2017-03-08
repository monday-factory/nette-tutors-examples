<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use App\Components\CommentsControl\ICommentsControlFactory;

class HomepagePresenter extends Presenter
{

	/**
	 * @var Context
	 */
	private $db;

	/**
	 * @var ICommentsControlFactory
	 */
	private $commentsControlFactory;

	/**
	 * @var ActiveRow|NULL
	 */
	private $article;


	public function __construct(Context $db, ICommentsControlFactory $commentsControlFactory)
	{
		$this->db = $db;
		$this->commentsControlFactory = $commentsControlFactory;
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


	public function createComponentCommentsControl()
	{
		$control = $this->commentsControlFactory->create($this->article);

		$control->onCommentAdded[] = function() {
			$this->redirect('this');
		};

		return $control;
	}

}
