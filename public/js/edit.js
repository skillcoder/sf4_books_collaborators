const books = document.getElementById('books');

if (books) {
    books.addEventListener('click', e => {
        if (e.target.className === 'btn btn-success delete-item') {
            const book_id = e.target.getAttribute('data-id');
            const author_id = e.target.getAttribute('data-item');
            if (confirm('Uncheck item #'+book_id+'?')) {
                fetch(`/author/${author_id}/book/${book_id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        } else if (e.target.className === 'btn btn-danger add-item') {
			const book_id = e.target.getAttribute('data-id');
            const author_id = e.target.getAttribute('data-item');
            if (confirm('Check item #'+book_id+'?')) {
                fetch(`/author/${author_id}/book/${book_id}`, {
                    method: 'PUT'
                }).then(res => window.location.reload());
            }
		}
    });
}

