window.FileCard = class FileCard {
  static makeOnEdit(path, extension, name) {
    return event => {
      // prevent default button behavior
      event.preventDefault()

      // set up modal window
      let modal = new tingle.modal({ footer: true })
      modal.addFooterBtn('Отмена', 'tingle-btn tingle-btn--danger', modal.close)
      modal.addFooterBtn('Изменить', 'tingle-btn tingle-btn--primary', () => {
        const newName = modal
          .getContent()
          .getElementsByClassName('name-input')[0]
          .value

        fetchWithReload('changeFileName.php', {
          method: 'POST',
          headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ path, extension, name: newName }),
        })

        // close modal
        modal.close()
      })

      // modal content
      modal.setContent(`
        <h1>Новое имя</h1>
        <input type="text" class="name-input" placeholder="Имя файла" value="${name}" />
      `)

      // open modal
      modal.open()
    }
  }
  static makeOnRemove(path) {
    return event => {
      // prevent default button behavior
      event.preventDefault()

      fetchWithReload('removeFile.php', {
        method: 'POST',
        headers: {
          'Accept': 'application/json, text/plain, */*',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ path: path }),
      })
    }
  }

  constructor({ path, extension, name }) {
    // container
    const containerEl = document.createElement('a')
    containerEl.classList.add('filecard-container')
    containerEl.href = 'uploads' + path

    // name
    const nameEl = document.createElement('div')
    nameEl.classList.add('filecard-name')
    nameEl.innerHTML = name

    // type
    const typeEl = document.createElement('div')
    typeEl.classList.add('filecard-type')
    typeEl.innerHTML = 
      FILE_EXTENSIONS[extension] || `Неизвестное расширение (${extension})`
    
    // thumbnail
    const thumbnailEl = new FileThumbnail(path)

    // --- buttons ---
    // edit button
    const editButtonEl = new CardButton('✎', ['edit-btn'], FileCard.makeOnEdit(path, extension, name))
    // remove button
    const removeButtonEl = new CardButton('🞬', ['remove-btn'], FileCard.makeOnRemove(path))
    // ---

    // build the card
    containerEl.append(
      thumbnailEl, 
      nameEl, 
      typeEl, 
      editButtonEl, 
      removeButtonEl
    )

    this = containerEl
  }
}