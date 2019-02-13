class Pagination {
    constructor(parentNode, limit) {
        this.parentNode = parentNode;
        this.limit = limit;
        this.totalCount = null;
    }

    setTotalCount(totalCount) {
        this.totalCount = totalCount;
    }

    createPagination() {
        const totalButtonsCount = Math.ceil(this.totalCount / this.limit);
        for (let i = 0; i < totalButtonsCount; i++) {
            this.addButton(this.createButton(i))
        }
    }

    createButton(i) {
        const button = document.createElement('button');
        button.dataset.index = i;
        button.innerText = i + 1;
        button.className = 'page-link';

        button.addEventListener('click', () => {
            getBooksWithLimitAndOffset(this.limit, i * this.limit);
        });

        return button;
    }

    addButton(button) {
        this.parentNode.appendChild(button);
    }
}
