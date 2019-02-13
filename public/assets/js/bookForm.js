const addBookForm = document.querySelector('#book-form');
const authorsSelect = document.querySelector('#book-form__author');
const fileInput = document.querySelector('#book-form__file');
const urlParams = new URLSearchParams(window.location.search);
const bookId = urlParams.get('id') || null;
let bookData = {};

addBookForm.addEventListener('submit', event => {
    event.preventDefault();
    sendBook();
});

fileInput.addEventListener('change', () => {
    validateFile(fileInput);
});

function sendBook() {
    const formData = new FormData(addBookForm);
    const book = {};
    const options = {
        method: fileInput.value ? 'POST': 'PATCH',
        url: '/api/books',
        data: fileInput.value ? formData : book
    };

    if (fileInput.value) {
        options.headers = { 'content-type': 'application/x-www-form-urlencoded' };

        if (bookId) {
            formData.append('bookId', bookId);
            formData.append('_method', 'PATCH');
        }
    } else {
        for (const [key, value] of formData.entries()) {
            book[key] = value;
        }

        if (bookId) {
            book.bookId = bookId;
            book.bookPicturePath = bookData.picturePath
        }
    }

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

function addAuthorsToSelect(authors) {
    authors.forEach(author => {
        const option = document.createElement('option');
        option.value = author.id;
        option.innerText = author.fullName;

        authorsSelect.appendChild(option);
    });
}


(function getAuthors() {
    axios.get('/api/authors')
        .then(response => {
            addAuthorsToSelect(response.data);
        })
        .catch(error => {
            console.error(error);
        });
})();

if (bookId) {
    const bookId = urlParams.get('id');

    axios.get('/api/books?id=' + bookId)
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
        const fileInput = document.querySelector('#book-form__file');
        const imageTitle = document.createElement('h6');
        const image = document.createElement('img');

        imageTitle.innerText = 'Current image: ' + book.picturePath.split('/')[1];

        image.className = 'img-fluid';
        image.src = book.picturePath;

        fileInput.parentNode.insertBefore(imageTitle, fileInput.nextSibling);
        imageTitle.parentNode.insertBefore(image, imageTitle.nextSibling);
    }

    bookData = book;
}
