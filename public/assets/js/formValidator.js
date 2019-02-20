const VALID_FILE_EXTENSIONS = ['.jpg', '.jpeg', '.png'];

function validateFile(fileInput) {
    const fileName = fileInput.value;
    if (fileName) {
        const extension = fileName.substr(fileName.lastIndexOf('.'));

        if (!VALID_FILE_EXTENSIONS.includes(extension)) {
            alert('Choose a file with one of the valid extensions ' + VALID_FILE_EXTENSIONS.join(', '));
            fileInput.value = null;
        }
    }
}
