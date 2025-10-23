<form action="upload_event_gallery.php" method="post" enctype="multipart/form-data">
    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" required>

    <label for="images">Upload Images:</label>
    <input type="file" name="images[]" multiple required>

    <button type="submit">Upload</button>
</form>
