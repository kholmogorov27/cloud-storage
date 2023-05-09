<!DOCTYPE html>
<html>

<head>
	<title>Загрузка файлов</title>
	<link rel="stylesheet" href="styles/main.css">
	<link rel="stylesheet" href="styles/filecard.css">
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
			<option value="ext-asc">Типу (по возрастанию)</option>
			<option value="ext-desc">Типу (по убыванию)</option>
		</select>
	</div>
	<div id="file-list"></div>

	<!-- scripts section -->
	<script src="js/extensions.js"></script>
	<script>
		async function getFiles() {
			const files = await fetch('getFiles.php')
				.then(response => response.json())
			const listEl = document.getElementById('file-list')

			files.forEach(file => {
				const containerEl = document.createElement('div')
				containerEl.classList.add('filecard-container')

				const nameEl = document.createElement('div')
				nameEl.classList.add('filecard-name')
				nameEl.innerHTML = file.name

				const typeEl = document.createElement('div')
				typeEl.classList.add('filecard-type')
				typeEl.innerHTML = FILE_EXTENSIONS[file.extension]

				const thumbnailContainerEl = document.createElement('div')
				thumbnailContainerEl.classList.add('filecard-thumbnail')

				const thumbnailEl = document.createElement('img')
				thumbnailEl.src = 'uploads/thumbnails' + file.path
				thumbnailEl.addEventListener('error', e => {
					const path = "assets/fallbackFileThumbnail.png"
					if (e.target.src !== path) {
						e.target.src = path
					}
				})

				thumbnailContainerEl.append(thumbnailEl)
				containerEl.append(thumbnailContainerEl, nameEl, typeEl)

				containerEl.setAttribute('data-date', file.date)
				containerEl.setAttribute('data-path', file.path)
				containerEl.setAttribute('data-ext', file.extension)

				listEl.append(containerEl)
			})
		}

		function sortFiles() {
			const fileList = document.getElementById("file-list");
			const sortBy = document.getElementById("sort-by").value;
			const files = Array.from(fileList.children);

			files.sort((a, b) => {
				switch (sortBy) {
					case 'name-asc':
						return a.textContent.localeCompare(b.textContent);

					case 'name-desc':
						return b.textContent.localeCompare(a.textContent);

					case 'date-asc':
						return Number(a.getAttribute('data-date')) - Number(b.getAttribute('data-date'));

					case 'date-desc':
						return Number(b.getAttribute('data-date')) - Number(a.getAttribute('data-date'));

					case 'ext-asc':
						return a.getAttribute('data-ext').localeCompare(b.getAttribute('data-ext'));

					case 'ext-desc':
						return b.getAttribute('data-ext').localeCompare(a.getAttribute('data-ext'));

					default:
						break
				}
			})

			while (fileList.firstChild) {
				fileList.removeChild(fileList.firstChild);
			}

			files.forEach(file => {
				fileList.appendChild(file);
			});
		}

		(() => {
			getFiles()
				.then(sortFiles)
		})()
	</script>
</body>

</html>