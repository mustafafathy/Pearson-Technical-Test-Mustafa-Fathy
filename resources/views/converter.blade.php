<!DOCTYPE html>
<html>

<head>
    <title>Pearson Technincal Test - Mustafa Fathy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="p-5">
        <div>
            <h4>Pearson Technincal Test - Mustafa Fathy</h4>
        </div>
        <div>
            <form id="csvUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="csvFile" class="form-label">Select CSV File</label>
                    <input class="form-control" type="file" id="csvFile" name="csv_file" accept=".csv">
                </div>
                <button type="submit" class="btn btn-primary">
                    Convert to JSON
                </button>
            </form>

            <div class="mt-4" id="resultSection" style="display: none;">
                <h5>Conversion Result:</h5>
                <div class="alert alert-success" id="successMessage" style="display: none;">
                    Data Converted successfully!
                </div>
                <div class="alert alert-danger" id="errorMessage" style="display: none;">
                    Error: <span id="errorText"></span>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <h6>JSON Output:</h6>
                    </div>
                    <pre id="jsonResult" class="bg-light p-3 rounded mt-2"></pre>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function () {
            $('#csvUploadForm').on('submit', function (e) {
                e.preventDefault();

                const file = $('#csvFile')[0].files[0];
                if (!file) {
                    showError('Please select a CSV file');
                    return;
                }

                const formData = new FormData(this);

                $('#successMessage, #errorMessage').hide();

                $.ajax({
                    url: '/api/convert-csv',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#jsonResult').text(JSON.stringify(response, null, 2));
                        $('#successMessage').show();
                        $('#resultSection').show();
                    },
                    error: function (xhr) {
                        const error = xhr.responseJSON?.message || 'Server error';
                        showError(error);
                        $('#resultSection').show();
                    }
                });
            });

            function showError(msg) {
                $('#errorText').text(msg);
                $('#errorMessage').show();
                $('#successMessage').hide();
            }
        });
    </script>
</body>

</html>