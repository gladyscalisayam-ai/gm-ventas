$(function () {
  $('#tbllistado').DataTable({
    ajax: { url: '../ajax/permiso.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
});
