const DEFAULT_LIMIT = 10;
const mainList = document.querySelector('#book-list');
const pagination = new Pagination(document.querySelector('#pagination'), DEFAULT_LIMIT);
let books = {};

function getTotalBooksCount() {
    axios.get('/api/books?totalCount')
        .then(response => {
            pagination.setTotalCount(response.data.totalCount);
            pagination.createPagination();
        })
        .catch(error => {
            console.error(error);
        });
}

function getBooksWithLimitAndOffset(limit, offset = 0) {
    if (books && books[limit] && books[limit][offset]) {
        buildBooksList(books[limit][offset]);

        return;
    }

    axios.get(`/api/books?limit=${limit}&offset=${offset}`)
        .then(response => {
            books[limit] = books[limit] || {};
            books[limit][offset] = [...response.data];

            buildBooksList(response.data);
        })
        .catch(error => {
            console.error(error);
        });
}

function buildBooksList(books) {
    mainList.innerHTML = '';

    books.forEach(book => {
        const li = document.createElement('li');
        li.id = 'book-' + book.id;
        li.className = 'list-group-item';

        const title = document.createElement('h3');
        const description = document.createElement('p');
        const authorInfo = document.createElement('p');
        const editButton = document.createElement('a');
        const deleteButton = document.createElement('button');
        let image = null;

        title.className = 'row justify-content-md-center';
        title.innerText = book.name;

        description.className = 'row justify-content-md-center';
        description.innerText = book.description;

        authorInfo.className = 'row justify-content-md-end';
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

        if (image) {
            li.appendChild(image);
        }

        li.appendChild(description);
        li.appendChild(authorInfo);

        li.appendChild(editButton);
        li.appendChild(deleteButton);

        mainList.appendChild(li);

        deleteButton.addEventListener('click', (event) => {
            deleteBook(event);
        });
    });
}

function deleteBook(event) {
    const bookId = event.target.dataset.index;

    axios.delete('/api/books?id=' + bookId)
        .then(response => {
            if (response.status === 204) {
                removeBookFromList(bookId);
            }
        })
        .catch(error => {
            console.error(error);
        })
}

function removeBookFromList(bookId) {
    document.querySelector('#book-' + bookId).remove();
}

(function init() {
    getTotalBooksCount();
    getBooksWithLimitAndOffset(DEFAULT_LIMIT);
})();
