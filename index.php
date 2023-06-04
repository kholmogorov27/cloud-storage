<!DOCTYPE html>
<html>

<head>
	<title>Загрузка файлов</title>
	<link rel="stylesheet" href="styles/main.css">
	<link rel="stylesheet" href="styles/filecard.css">
	<link rel="stylesheet" href="styles/tingle.css">
</head>

<body>
	<h1>Загрузка файлов</h1>
	<form action="upload.php" method="post" enctype="multipart/form-data"> <label for="file">Выберите файл:</label> <input
			type="file" name="file" id="file"><br><br> <input type="submit" name="submit" value="Загрузить"> </form> <br><br>
	<h2>Список загруженных файлов</h2>
	<div class="sort">
		<label for="sort-by">Сортировать по:</label>
		<select id="sort-by" onchange="sortFiles()" valu>
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
	<script src="js/fetchWithReload.js"></script>
	<script src="js/tingle.js"></script>
	<script>
		async function getFiles() {
			const files = await fetch('getFiles.php')
				.then(response => response.json())
			const listEl = document.getElementById('file-list')

			files.forEach(file => {
				const containerEl = document.createElement('a')
				containerEl.href = 'uploads' + file.path
				containerEl.classList.add('filecard-container')

				const nameEl = document.createElement('div')
				nameEl.classList.add('filecard-name')
				nameEl.innerHTML = file.name

				const typeEl = document.createElement('div')
				typeEl.classList.add('filecard-type')
				typeEl.innerHTML = FILE_EXTENSIONS[file.extension] || `Неизвестное расширение (${file.extension})`

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

				const editButtonEl = document.createElement('button')
				editButtonEl.classList.add('edit-btn')
				editButtonEl.innerHTML = '✎'
				editButtonEl.addEventListener('click', e => {
					e.preventDefault()

					let modal = new tingle.modal({
						footer: true
					})

					modal.addFooterBtn('Отмена', 'tingle-btn tingle-btn--danger', function () {
						modal.close()
					})

					modal.addFooterBtn('Изменить', 'tingle-btn tingle-btn--primary', function () {
						const newName = modal.getContent().getElementsByClassName('name-input')[0].value
						fetchWithReload('changeFileName.php', {
							method: 'POST',
							headers: {
								'Accept': 'application/json, text/plain, */*',
								'Content-Type': 'application/json'
							},
							body: JSON.stringify({ path: file.path, name: newName, extension: file.extension }),
						})
						modal.close()
					})

					modal.setContent(`
						<h1>Новое имя</h1>
						<input type="text" class="name-input" placeholder="Имя файла" value="${file.name}" />
					`)
					modal.open()

				})

				const removeButtonEl = document.createElement('button')
				removeButtonEl.classList.add('remove-btn')
				removeButtonEl.innerHTML = '🞬'
				removeButtonEl.addEventListener('click', e => {
					e.preventDefault()
					fetchWithReload('removeFile.php', {
						method: 'POST',
						headers: {
							'Accept': 'application/json, text/plain, */*',
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ path: file.path }),
					})
				})

				thumbnailContainerEl.append(thumbnailEl)
				containerEl.append(thumbnailContainerEl, nameEl, typeEl, editButtonEl, removeButtonEl)

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

			sessionStorage.setItem('sort', sortBy)

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

			const sortFromStorage = sessionStorage.getItem('sort')
			if (sortFromStorage) {
				const sortBy = document.getElementById("sort-by").value = sortFromStorage
			}
		})()
	</script>
</body>

</html>