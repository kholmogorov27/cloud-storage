<!DOCTYPE html>
<html>

<head>
	<title>Загрузка файлов</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f5f5f5;
		}

		h1 {
			font-size: 36px;
			font-weight: bold;
			color: #333;
			margin-top: 30px;
			margin-bottom: 20px;
			text-align: center;
		}

		h2 {
			font-size: 24px;
			font-weight: bold;
			color: #333;
			margin-top: 30px;
			margin-bottom: 20px;
			text-align: center;
		}

		form {
			margin: 0 auto;
			width: 500px;
			background-color: #fff;
			border: 1px solid #ccc;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		}

		label {
			display: block;
			font-size: 18px;
			font-weight: bold;
			color: #333;
			margin-bottom: 10px;
		}

		input[type="file"] {
			font-size: 16px;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			background-color: #fff;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}

		input[type="submit"] {
			font-size: 18px;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			background-color: #4CAF50;
			color: #fff;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		input[type="submit"]:hover {
			background-color: #3e8e41;
		}

		ul {
			list-style: none;
			margin: 0;
			padding: 0;
			text-align: center;
		}

		li {
			font-size: 18px;
			margin-bottom: 10px;
		}

		a {
			color: #333;
			transition: color 0.3s ease;
		}

		a:hover {
			color: #4CAF50;
		}

		.sort {
			width: 100%;
			text-align: center;
			display: inline-block;
			margin-bottom: 2em;
		}

		.sort label {
			display: inline;
			font-size: 18px;
			font-weight: bold;
			color: #333;
			margin-right: 10px;
		}

		.sort select {
			font-size: 16px;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			background-color: #fff;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}
	</style>
</head>

<body>
	<h1>Загрузка файлов</h1>
	<form action="upload.php" method="post" enctype="multipart/form-data"> <label for="file">Выберите файл:</label> <input
			type="file" name="file" id="file"><br><br> <input type="submit" name="submit" value="Загрузить"> </form> <br><br>
	<h2>Список загруженных файлов</h2>
	<div class="sort">
		<label for="sort-by">Сортировать по:</label>
		<select id="sort-by" onchange="sortFiles()">
			<option value="name-asc">Имени (по возрастанию)</option>
			<option value="name-desc">Имени (по убыванию)</option>
			<option value="date-asc">Дате (по возрастанию)</option>
			<option value="date-desc">Дате (по убыванию)</option>
		</select>
	</div>
	<ul id="file-list">
		<?php
		// Получаем список файлов в директории uploads 
		$files = scandir("uploads");
		// Удаляем первые два элемента массива, которые соответствуют текущей и родительской директориям
		unset($files[0]);
		unset($files[1]);

		// Выводим список файлов
		foreach ($files as $file) {
			echo '<li><a href="uploads/' . $file . '">' . $file . '</a></li>';
		}
		?>
	</ul>
	<script> function sortFiles() {
			const fileList = document.getElementById("file-list"); const sortBy = document.getElementById("sort-by").value;
			const files = Array.from(fileList.children);
			files.sort((a, b) => {
				const aName = a.firstChild.textContent;
				const bName = b.firstChild.textContent;
				const aDate = new Date(a.lastChild.textContent);
				const bDate = new Date(b.lastChild.textContent);

				if (sortBy === "name-asc") {
					return aName.localeCompare(bName);
				} else if (sortBy === "name-desc") {
					return bName.localeCompare(aName);
				} else if (sortBy === "date-asc") {
					return aDate.getTime() - bDate.getTime();
				} else if (sortBy === "date-desc") {
					return bDate.getTime() - aDate.getTime();
				}
			});

			while (fileList.firstChild) {
				fileList.removeChild(fileList.firstChild);
			}

			files.forEach(file => {
				fileList.appendChild(file);
			});
		}
	</script>
</body>

</html>