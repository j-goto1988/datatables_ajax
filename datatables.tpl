<!doctype html>
<html>
  <head>
  <meta charset="UTF-8">
  <title>サンプル</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
  <script type="text/javascript" src="DataTables/datatables.min.js"></script>
  </head>
  <body>

    <table id="sample_table" border="1">
      <thead>
        <tr>
          <th>name</th>
          <th>number</th>
        </tr>
      </thead>
    </table>

    <script type="text/javascript">
      $(document).ready(function(){
        var table = $("#sample_table").DataTable({
          language: {
            url: "//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Japanese.json"
          },
          // 件数切替機能 無効
          lengthChange: true,
          // 検索機能 無効
          searching: true,
          // ソート機能 無効
          ordering: true,
          // 情報表示 無効
          info: true,
          // ページング機能 無効
          paging: true,
          // 処理中を表示
          processing: true,
          // サーバー通信
          serverSide: true,
          ajax: {
            url: "soccer_ajax.php",
            "data": function (d) {
              d.free_word = '0';
            },
            dataSrc: "data"
          },
          columns: [
          { data: "name"},
          { data: "number"}
          ],
        })
      });
    </script>
  </body>
</html>