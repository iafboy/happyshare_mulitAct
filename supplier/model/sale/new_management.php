<?php
	class ModelSaleNewManagment extends Model {
		public function order_data_link()
		{
			$link = mysqli_connect('localhost','root','likun','lfx20151130');
			return $link;
		}
		public function new_search($dataarray, $supplierID){
			
		}
	}
?>