<?php

class PostColumns
{
	/**
	 * Post type name
	 *
	 * @var string
	 */
	private $postType;

	public function __construct(string $postType)
	{
		$this->postType = $postType;
	}

	/**
	 * Adds filter to posts columns of the post type
	 *
	 * @param mixed $callable
	 * @return void
	 */
	public function add($callable)
	{
		add_filter("manage_{$this->postType}_posts_columns", $callable, 10, 1);
	}

	/**
	 * Adds action to custom column
	 *
	 * @param mixed $callable
	 * @return void
	 */
	public function populate($callable)
	{
		add_action("manage_{$this->postType}_posts_custom_column", $callable, 10, 2);
	}
}