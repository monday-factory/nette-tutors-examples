<?php

declare(strict_types=1);

namespace App\Components\CommentDetailControl;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;

class CommentDetailControl extends Control
{

	public function render(ActiveRow $comment): void
	{
		$this->getTemplate()->comment = $comment;

		$this->getTemplate()->render(__DIR__ . '/templates/commentDetailControl.latte');
	}

}
