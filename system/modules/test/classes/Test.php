<?php

namespace Test;

class Test extends System
{
	public function test()
	{
		global $objPage;
		$objTestModel = \PageModel::findByPk($objPage->id);

		var_dump($objTestModel->returnDummy());
	}
}
