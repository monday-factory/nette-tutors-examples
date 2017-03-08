<?php

declare(strict_types=1);

namespace App\Components\CommentDetailControl;

interface ICommentDetailControlFactory
{

	public function create(): CommentDetailControl;

}
