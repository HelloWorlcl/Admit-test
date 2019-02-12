const mainList = document.querySelector('#book-list');

function addBookToList(data) {
    data.forEach(book => {
        const li = document.createElement('li');
        li.id = 'book-' + book.id;
        li.className = 'list-group-item';

        const title = document.createElement('h5');
        const description = document.createElement('p');
        const authorInfo = document.createElement('p');
        const editButton = document.createElement('a');
        const deleteButton = document.createElement('button');
        let image = null;

        title.innerText = book.name;
        description.innerText = book.description;
        authorInfo.innerText = book.author.fullName;

        if (book.picturePath) {
            image = document.createElement('img');
            image.className = 'img-fluid';
            image.src = book.picturePath;
        }

        editButton.className = 'edit-book btn btn-warning';
        editButton.href = 'book-form.html?id=' + book.id;
        editButton.innerText = 'Edit';
        editButton.dataset.index = book.id;

        deleteButton.className = 'delete-book btn btn-danger';
        deleteButton.innerText = 'Delete';
        deleteButton.dataset.index = book.id;

        li.appendChild(title);
        li.appendChild(description);
        li.appendChild(authorInfo);

        if (image) {
            li.appendChild(image);
        }

        li.appendChild(editButton);
        li.appendChild(deleteButton);

        mainList.appendChild(li);
    });
}

axios.get('/books')
    .then(response => {
        addBookToList(response.data);
    })
    .catch(error => {
        console.log(error);
    });
