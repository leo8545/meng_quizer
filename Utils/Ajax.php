<?php

class Ajax
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
	 * Set ajax action hooks for the post type
	 *
	 * @param mixed $callable
	 * @return void
	 */
	public function ajax($callable)
	{
		add_action("wp_ajax_action_{$this->postType}", $callable);
		add_action("wp_ajax_nopriv_action_{$this->postType}", $callable);
	}
}