const authors = document.getElementById('table');

if (authors) {
	authors.addEventListener('click', e => {
		if (e.target.className === 'btn btn-danger delete-item') {
			const id = e.target.getAttribute('data-id');
			const item = e.target.getAttribute('data-item');
			if (confirm('Delete item #'+id+'?')) {
				fetch(`/${item}/delete/${id}`, {
					method: 'DELETE'
				}).then(res => window.location.reload());
			}
		}
	});
}

