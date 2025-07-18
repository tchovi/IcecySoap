<?php require_once '../inc/header.php'; ?>

<div class="container mt-4">
    <h2>Media Library</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="musicFile" accept=".mp3">
            <label class="custom-file-label" for="musicFile">Choose MP3</label>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    
    <table class="table table-striped mt-4">
        <thead>
            <tr><th>Title</th><th>Artist</th><th>Plays</th><th>Actions</th></tr>
        </thead>
        <tbody id="mediaList"></tbody>
    </table>
</div>

<script>
$('#uploadForm').submit(function(e){
    e.preventDefault();
    let formData = new FormData();
    formData.append('file', $('#musicFile')[0].files[0]);
    
    $.ajax({
        url: '/api/songs.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: () => loadMedia()
    });
});

function loadMedia() {
    $.get('/api/songs.php', data => {
        $('#mediaList').empty();
        data.forEach(song => {
            $('#mediaList').append(`<tr>
                <td>${song.title}</td>
                <td>${song.artist}</td>
                <td>${song.play_count}</td>
                <td><button class="btn btn-sm btn-danger">Delete</button></td>
            </tr>`);
        });
    });
}
</script>
<?php require_once '../inc/footer.php'; ?>
