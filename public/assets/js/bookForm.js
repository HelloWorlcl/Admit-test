const BOOKS_DEFAULT_URL = 'api/books';
const AUTHORS_DEFAULT_URL = 'api/authors';
const addBookForm = document.querySelector('#book-form');
const authorsSelect = document.querySelector('#book-form__author');
const fileInput = document.querySelector('#book-form__file');
const urlParams = new URLSearchParams(window.location.search);
let bookData = {};

(function init() {
    const bookId = urlParams.get('id');

    getAuthors();

    if (bookId) {
        getBook(bookId);
    }

    addBookForm.addEventListener('submit', event => {
        event.preventDefault();
        sendBook(bookId);
    });
    fileInput.addEventListener('change', () => {
        validateFile(fileInput);
    });
})();

function getAuthors() {
    axios.get(AUTHORS_DEFAULT_URL)
        .then(response => {
            addAuthorsToSelect(response.data);
        })
        .catch(error => {
            console.error(error);
        });
}

function addAuthorsToSelect(authors) {
    authors.forEach(author => {
        const option = document.createElement('option');
        option.value = author.id;
        option.innerText = author.fullName;

        authorsSelect.appendChild(option);
    });
}

function getBook(bookId) {
    axios.get(`${BOOKS_DEFAULT_URL}?id=${bookId}`)
        .then(response => {
            fillInputsWithBookData(response.data);
        })
        .catch(error => {
            console.error(error);
        });
}

function fillInputsWithBookData(book) {
    document.querySelector('#book-form__name').value = book.name;
    document.querySelector('#book-form__author').value = book.author.id;
    document.querySelector('#book-form__description').value = book.description;

    if (book.picturePath) {
        insertCurrentPicture(book.picturePath);
    }

    bookData = { ...book };
}

function insertCurrentPicture(picturePath) {
    const fileInput = document.querySelector('#book-form__file');
    const imageTitle = document.createElement('h6');
    const image = document.createElement('img');

    imageTitle.innerText = 'Current image: ' + picturePath.split('/')[1];
    image.className = 'img-fluid';
    image.src = picturePath;

    fileInput.parentNode.insertBefore(imageTitle, fileInput.nextSibling);
    imageTitle.parentNode.insertBefore(image, imageTitle.nextSibling);
}

function sendBook(bookId) {
    const formData = new FormData(addBookForm);
    const options = createRequestOptions(formData, bookId);

    axios(options)
        .then(response => {
            if (response.status === 200) {
                window.location.replace('/');
            }
        })
        .catch(error => {
            console.error(error);
        });
}

function createRequestOptions(formData, bookId) {
    const options = {
        url: BOOKS_DEFAULT_URL,
    };
    let method = 'POST';
    let data = null;

    if (fileInput.value) {
        options.headers = { 'content-type': 'application/form-data' };

        if (bookId) {
            formData.append('id', bookId);
            formData.append('_method', 'PUT');
        }

        data = formData;
    } else {
        const book = {};

        for (const [key, value] of formData.entries()) {
            book[key] = value;
        }

        if (bookId) {
            book.id = bookId;
            book.bookPicturePath = bookData.picturePath;
            method = 'PUT';
        }

        data = { ...book };
    }

    options.method = method;
    options.data = data;

    return options;
}
