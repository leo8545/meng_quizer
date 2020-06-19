<?php

class PostColumns
{
	private $postType;

	public function __construct(string $postType)
	{
		$this->postType = $postType;
	}

	public function add($callable)
	{
		add_filter("manage_{$this->postType}_posts_columns", $callable, 10, 1);
	}

	public function populate($callable)
	{
		add_action("manage_{$this->postType}_posts_custom_column", $callable, 10, 2);
	}
}