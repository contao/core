 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 *
 *
 *
 *
 *
 *
 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	/**
	 * Recursively copy a directory
	 *
	 * @param string $strSource      The source file or folder
	 * @param string $strDestination The new file or folder path
	 */
	public function rcopy($strSource, $strDestination)
	{
		$this->validate($strSource, $strDestination);

		$this->mkdir($strDestination);
		$arrFiles = scan(TL_ROOT . '/' . $strSource);

		foreach ($arrFiles as $strFile)
		{
			if (is_dir(TL_ROOT . '/' . $strSource . '/' . $strFile))
			{
				$this->rcopy($strSource . '/' . $strFile, $strDestination . '/' . $strFile);
			}
			else
			{
				$this->copy($strSource . '/' . $strFile, $strDestination . '/' . $strFile);
			}
		}
	}


	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *