<?php

namespace QuadLayers\QLSE\Models;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;
use QuadLayers\QLSE\Entity\Settings as Settings_Entity;


/**
 * Models_Setting Class
 */
class Settings {

	protected static $instance;
	protected $repository;

	public function __construct() {
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'qlse_settings' )
		->setEntity( Settings_Entity::class );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Get the database table associated with the admin menu services.
	 *
	 * @return string
	 */
	public function get_table(): string {
		return $this->repository->getTable();
	}

	/**
	 * Retrieve the Settings entity from the repository.
	 * If no entity exists, a new Settings object is returned.
	 *
	 * @return Settings_Entity
	 */
	public function get() {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity;
		} else {
			$admin = new Settings_Entity();
			return $admin;
		}
	}

	/**
	 * Delete all menu services entities from the repository.
	 *
	 * @return bool
	 */
	public function delete_all() {
		return $this->repository->delete();
	}

	/**
	 * Save the admin menu services entity to the repository.
	 *
	 * @param array $data The data for the new chatbot entity.
	 * @return bool
	 */
	public function save( $data ) {
		return $this->repository->create( $data );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
