<?php

declare(strict_types=1);

namespace App\Components\CommentsControl;

use Nette\Database\Table\ActiveRow;

interface ICommentsControlFactory
{

	public function create(ActiveRow $article): CommentsControl;

}
