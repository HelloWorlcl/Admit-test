const mainList = document.querySelector('#book-list');

function addBookToList(data) {
    data.forEach(book => {
        const li = document.createElement('li');
        li.className = 'list-group-item';

        const title = document.createElement('h5');
        const description = document.createElement('p');
        const authorInfo = document.createElement('p');
        const editButton = document.createElement('button');
        const deleteButton = document.createElement('button');

        title.innerText = book.name;
        description.innerText = book.description;
        authorInfo.innerText = book.author.fullName;

        editButton.className = 'btn btn-warning';
        editButton.innerText = 'Edit';
        editButton.dataset.index = book.id;

        deleteButton.className = 'btn btn-danger';
        deleteButton.innerText = 'Delete';
        deleteButton.dataset.index = book.id;

        li.appendChild(title);
        li.appendChild(description);
        li.appendChild(authorInfo);
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
