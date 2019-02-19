const BOOKS_DEFAULT_URL = 'api/books';
const DEFAULT_LIMIT = 10;
const mainList = document.querySelector('#book-list');
const pagination = new Pagination(document.querySelector('#pagination'), DEFAULT_LIMIT);
let books = {};

function getTotalBooksCount() {
    axios.get(BOOKS_DEFAULT_URL + '?totalCount')
        .then(response => {
            pagination.setTotalCount(response.data.totalCount);
            pagination.createPagination();
        })
        .catch(error => {
            console.error(error);
        });
}

function getBooksWithLimitAndOffset(limit, offset = 0) {
    if (books[limit] && books[limit][offset]) {
        buildBooksList(books[limit][offset]);

        return;
    }

    axios.get(`${BOOKS_DEFAULT_URL}?limit=${limit}&offset=${offset}`)
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
    clearBooksList();
    books.forEach(book => createBookListElement(book));
}

function clearBooksList() {
    while (mainList.firstChild) {
        mainList.firstChild.remove();
    }
}

function createBookListElement(book) {
    const li = createListElement(book.id);
    const image = createBookImage(book.picturePath);

    li.appendChild(createBookTitle(book.name));
    if (image) {
        li.appendChild(image);
    }
    li.appendChild(createBookDescription(book.description));
    li.appendChild(createAuthorInfo(book.author.fullName));
    li.appendChild(createButtons(book.id));

    mainList.appendChild(li);
}

function createListElement(bookId) {
    const li = document.createElement('li');

    li.id = 'book-' + bookId;
    li.className = 'list-group-item';

    return li;
}

function createBookTitle(bookTitle) {
    const title = document.createElement('h3');

    title.className = 'row justify-content-md-center';
    title.innerText = bookTitle;

    return title;
}

function createBookImage(bookPicturePath) {
    let image = null;

    if (bookPicturePath) {
        image = document.createElement('img');
        image.className = 'img-fluid';
        image.src = bookPicturePath;
    }

    return image;
}

function createBookDescription(bookDescription) {
    const description = document.createElement('p');

    description.className = 'row justify-content-md-center mt-2';
    description.innerText = bookDescription;

    return description;
}

function createAuthorInfo(authorFullName) {
    const authorInfo = document.createElement('h4');

    authorInfo.className = 'row justify-content-md-center';
    authorInfo.innerText = authorFullName;

    return authorInfo;
}

function createButtons(bookId) {
    const buttonsBlock = document.createElement('div');

    buttonsBlock.className = 'row justify-content-md-end';

    buttonsBlock.appendChild(createEditButton(bookId));
    buttonsBlock.appendChild(createDeleteButton(bookId));

    return buttonsBlock;
}

function createEditButton(bookId) {
    const editButton = document.createElement('a');

    editButton.className = 'edit-book btn btn-warning mr-3';
    editButton.href = 'book-form.html?id=' + bookId;
    editButton.innerText = 'Edit';
    editButton.dataset.index = bookId;

    return editButton;
}

function createDeleteButton(bookId) {
    const deleteButton = document.createElement('button');

    deleteButton.className = 'delete-book btn btn-danger mr-3';
    deleteButton.innerText = 'Delete';
    deleteButton.dataset.index = bookId;

    deleteButton.addEventListener('click', (event) => {
        deleteBook(event);
    });

    return deleteButton;
}

function deleteBook(event) {
    const bookId = event.target.dataset.index;

    axios.delete(`${BOOKS_DEFAULT_URL}?id=${bookId}`)
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
    books = {};
    pagination.rebuildPaginationAfterItemDelete();
}

(function init() {
    getTotalBooksCount();
    getBooksWithLimitAndOffset(DEFAULT_LIMIT);
})();
