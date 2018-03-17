<?php
	require_once "article.php";
	class ModelTextFiles
	{
		public $path;

		public function __construct(string $path)
		{
			$this->path = $path;
		}
		//вернуть $number статей начиная со статьи, имеющей позицию $offset по отношению к самой новой странице(у нее позиция - 0).
		public function getNArticles($offset, $number)
		{
			//определить id нужной статьи
			for($curId = $this->getLastId(), $curPos = 0; $curId &&  $curPos != $offset; --$curId) {
				if(file_exists("{$this->path}/$curId"))
					++$curPos;
			}
			//начать считывать в массив $articles нужное количество статей
			for(; $number && $curId; --$curId) {
				if(file_exists("{$this->path}/$curId")) {
					--$number;

					$file = fopen("{$this->path}/$curId", "rt");
					$articleName = fgets($file);
					$articleAuthor = fgets($file);
					$articleCreationDate = fgets($file);
					$articleContent = file_get_contents("{$this->path}/$curId", false, null, ftell($file));
					fclose($file);

					$articles[] = new Article($curId, $articleName, $articleContent, $articleAuthor, $articleCreationDate);
				}
			}
			return $articles;
		}
		//сохранить новую статью
		public function saveNewArticle(Article $article)
		{
			//новый id
			$article->id = $this->getLastId() + 1;
			//Создать файл
			$newFileName = (string)$article->id;
			if(($file = fopen("{$this->path}/$newFileName", "xt")) === false)
				throw new Exception("Failed to create a new file!");

			$lastIdFileName = "{$this->path}/lastid.data";
			$lastIdFile = fopen($lastIdFileName, "w+t") or die("Не могу открыть файл!");
			//Записать в файл все данные
			fwrite($file, $article->name."\n");
			fwrite($file, $article->author."\n");
			fwrite($file, $article->creationDate."\n");
			fwrite($file, $article->content."\n");
			//изменить lastId
			fwrite($lastIdFile, $article->id);
			//Закрыть файлы
			fclose($file);
			fclose($lastIdFile);
		}
		//удалить статью
		public function deleteArticle($id) : bool
		{
			//если статья сущ.
			if(file_exists("{$this->path}/$id")) {
				//если статья является самой новой и ее ИД хранится в файле lastid.data
				if($id == ($lastId = $this->getLastId())) {
					//узнать ид предыдущей по номеру ид статьи
					for(--$lastId; $lastId && !file_exists("{$this->path}/$lastId"); --$lastId);

					//записать новый lastid
					$file = fopen("{$this->path}/lastid.data", "w+t");
					fwrite($file, $lastId);
					fclose($file);

					unlink("{$this->path}/$id");
				} else {
					unlink("{$this->path}/$id");
				}

				return true;
			} else {
				return false;
			}
		}
		//вернуть общее количество существующих статей
		public function getNumberOfArticles() : int
		{
			//открыть текущий каталог
			//посчитать количество файлов, не считая файл lastid.data
			$dir = opendir($this->path);

			$count = 0;
			while(false !== ($file = readdir($dir))) {
				++$count;
			}

			closedir($dir);

			return $count - 3; // три файла: ".", ".." и 'lastid.data'
		}
		//олучить ид последней статьи
		public function getLastId() : int
		{
			$fileName = "{$this->path}/lastid.data";

			$file = fopen($fileName, "rt") or die("Не могу открыть файл!");
			flock($file, LOCK_SH);
			$id = fgets($file);
			fclose($file);

			return (int)$id;
		}
		/*/вернуть последние статьи. Первый элемент массива - самая последняя статья
		public function getLastArticles(int $number)
		{
			//узнать ид самой новой статьи
			$lastId = $this->getLastId();
			//открывать и читать статьи, пока не наберется $number штук
			for($curId = $lastId; $number && $curId; --$curId) {
				if(!file_exists("{$this->path}/$curId"))
					continue;

				$file = fopen("{$this->path}/$curId", "rt");
			//	прочитать статью и записать в новый элемент массива статей
				$articleName = fgets($file);
				$articleAuthor = fgets($file);
				$articleCreationDate = fgets($file);
				$articleContent = file_get_contents("{$this->path}/$curId", false, null, ftell($file));

				$articles[] = new Article($id, $articleName, $articleContent, $articleAuthor, $articleCreationDate);
			//	закрыть файл
				fclose($file);
			}
			//вернуть массив статей
			return $articles;
		} */
		/*вернуть N статей, начиная с указанного ид по уменьшению ид
		public function getNFromUpperIdToLower($highestId, $number)
		{
			//рассчитать номер первой статьи start [lowestId, highestId]
			$lowestId = ($highestId - $number) > 0 ? $highestId - $number + 1 : 1;
			//В цикле создать массив статей в обратном порядке[lastid, startid]
			//	открыть очередной файл
			foreach(range($highestId, $lowestId) as $id) {
				$file = fopen("{$this->path}/$id", "rt");
				if(!$file)
					throw new Exception("Доделать проверку есть ли статья с таким id");
			//	прочитать статью и записать в новый элемент массива статей
				$articleName = fgets($file);
				$articleAuthor = fgets($file);
				$articleCreationDate = fgets($file);
				$articleContent = "";
				while(($content = fgets($file)) !== false)
					$articleContent .= $content;

				$articles[] = new Article($id, $articleName, $articleContent, $articleAuthor, $articleCreationDate);
			//	закрыть файл
				fclose($file);
			}
			//вернуть массив статей
			return $articles;
		} */
		/*вернуть N статей, начиная с указанного ид по возрастанию ид
		public function getNFromLowerIdToUpper($lowerId, $number)
		{
			$highestId = $lowerId + $number -1;
			foreach(range($highestId, $lowerId) as $id) {
				$file = fopen("{$this->path}/$id", "rt");
				if(!$file)
					throw new Exception("Доделать проверку есть ли статься с таким id");
				$articleName = fgets($file);
				$articleAuthor = fgets($file);
				$articleCreationDate = fgets($file);
				$articleContent = "";
				while(($content = fgets($file)) !== false)
					$articleContent .= $content;

				$articles[] = new Article($id, $articleName, $articleContent, $articleAuthor, $articleCreationDate);

				fclose($file);
			}
			return $articles;
		}
		*/
	}