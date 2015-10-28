<?php 

namespace controller;

require_once("model/BLL/Image.php");
require_once("model/BLL/Product.php");
require_once("model/BLL/Category.php");

require_once("view/HTMLView.php");
require_once("view/NavigationView.php");
require_once("view/CreateProductView.php");
require_once("view/HandleCategoryView.php");
require_once("view/ProductListView.php");
require_once("view/ProductView.php");

class AdminController {

	private $model;
	
	private $nv;
	private $cpv;
	private $plv;
	private $pv;
	private $hcv;

	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	public function __construct(\model\AdminModel $model, \view\NavigationView $nv) {
		$this->model = $model;
		$this->nv = $nv;

		$this->cpv = new \view\CreateProductView();
		$this->plv = new \view\ProductListView($this->model, $this->nv);	
	}

	public function doAdminControl() {

		if($this->nv->adminWantsToViewProduct()) {
			$product = $this->plv->getSelectedProduct();
			$this->pv = new \view\ProductView($this->nv, $product);
			$this->hcv = new \view\HandleCategoryView($this->nv, $this->model, $product);
		}

		if($this->nv->inProductView()) {
			if ($this->pv->adminConfirmUpdate()) {
				$updatedProduct = $this->pv->getUpdatedProduct();
				if($updatedProduct !== null) {
					try {
						$this->model->updateProduct($updatedProduct);
						$this->pv->redirect("Product successfully updated.");
					}
					catch(\PDOUniqueExistsException $e) {
						$_SESSION[self::$sessionMessage] = $e->getMessage();
					}
				} 
			}
			if ($this->pv->adminCancelUpdate()) {
				$this->pv->redirect("Product was not updated.");
			}
			if ($this->pv->adminConfirmDelete()) {
				try {
					$this->model->deleteProduct($this->pv->getProductID());
					$this->plv->redirect("Product successfully deleted from the database.");
				}
				catch(\PDOFetchObjectException $e) {
					$_SESSION[self::$sessionMessage] = $e->getMessage();
				}
				catch(\PDOFetchColumnException $e) {
					$_SESSION[self::$sessionMessage] = $e->getMessage();
				}
			}
			if ($this->pv->adminCancelDelete()) {
				$this->pv->redirect("Product was not deleted.");
			}	
			if ($this->hcv->adminWantsToCreateCategory()) {
				$c = $this->hcv->getCategory();

				if($c !== null) {
					try {
						$this->model->createCategory($c);
						$this->pv->redirect("Category successfully added to the database.");
					}
					catch(\PDOCategoryExistsException $e) {
						$_SESSION[self::$sessionMessage] = $e->getMessage();
					}
				}
			}
			if ($this->hcv->adminWantsToUpdateCategories()) {
				$this->hcv->updateCheckArrays();
		
				$addCategories = array();
				$addCategories = $this->hcv->getAddCategoryArray();
				$deleteCategories = array();
				$deleteCategories = $this->hcv->getDeleteCategoryArray();

				if(count($addCategories) > 0) {
					for ($i = 0; $i < count($addCategories); $i++) {
						$this->model->addProductCategory($this->pv->getProductID(), $addCategories[$i]);
					}
				}

				if(count($deleteCategories) > 0) {
					for ($j = 0; $j < count($deleteCategories); $j++) {
						$this->model->deleteProductCategory($this->pv->getProductID(), $deleteCategories[$j]);
					}	
				}
				$this->hcv->redirect("Product categories succesfully updated.");
			}
		} else {
			if($this->cpv->adminWantsToAddProduct()) {
				$image = $this->cpv->getImage();

				if($image !== null) {
					$p = $this->cpv->getProduct($image->getFilename());

					if($p !== null) {
						try {
							$this->model->createProduct($p, $image);
							$this->plv->redirect("Product successfully added to the database.");
						}
						catch(\PDOUniqueExistsException $e) {
							$_SESSION[self::$sessionMessage] = $e->getMessage();
						}
					}
				}
			}
			if($this->nv->adminWantsToDeleteProduct()) {
				$id = $this->nv->getProductID();

				if ($this->plv->adminConfirm()) {
					try {
						$this->model->deleteProduct($id);
						$this->plv->redirect("Product successfully deleted from the database.");
					}
					catch(\PDOFetchObjectException $e) {
						$_SESSION[self::$sessionMessage] = $e->getMessage();
					}
					catch(\PDOFetchColumnException $e) {
						$_SESSION[self::$sessionMessage] = $e->getMessage();
					}
				}
				if ($this->plv->adminCancel()) {
					$this->plv->redirect("Product was not deleted.");
				}
			}
			if($this->nv->adminWantsToUpdateProduct()) {
				$id = $this->nv->getProductID();
				$product = $this->model->getProductByID($id);

				if($this->plv->adminConfirm()) {
					$updatedProduct = $this->plv->getUpdatedProduct($product);

					if($updatedProduct !== null) {
						try {
							$this->model->updateProduct($updatedProduct);
							$this->plv->redirect("Product successfully updated.");
						}
						catch(\PDOUniqueExistsException $e) {
							$_SESSION[self::$sessionMessage] = $e->getMessage();
						}
					} 
				}
				if ($this->plv->adminCancel()) {
					$this->plv->redirect("Product was not updated.");
				}
			}
		}
	}

	/**
	 *	Returns a view depending on state of page
	 *	@return view
	 */
	public function getContainerView() {
		if($this->nv->inProductView()) {
			return $this->pv;
		}
		return $this->plv;
	}

	/**
	 *	Returns a view depending on state of page
	 *  @return view
	 */
	public function getAsideView() {
		if($this->nv->inProductView()) {
			return $this->hcv;
		}
		return $this->cpv;
	}
}