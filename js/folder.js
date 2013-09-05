// checkVersion('folder', 1);

function show_folder()
{
  submitPost({ action:'show_folder.php',
               parameters:{ folder_id:folder_id } } );
}

function update_folder()
{
  submitPost({ action:'update_folder.php',
               parameters:{ folder_id:folder_id } } );
}

function delete_folder()
{
  submitPost({ action:'delete_folder.php',
               parameters:{ folder_id:folder_id } } );
}

function next_folder()
{
  var length = top.setFolders_lth;

  if (length > 0) {
    var id        = folder_id;
    var set       = top.setFolders;
    var next_id   = -1;
    var i, val;
	for (i = 0; i < length; ++i) {
	  val = set[i];
	  if (val > id) {
        if (next_id == -1 || val < next_id) {
		  next_id = val;
    } } }
	if (next_id != -1) {
  	  submitPost({ action:myScript,
                   parameters:{ folder_id:next_id } } );
} } }

function prev_folder()
{
  var length = top.setFolders_lth;

  if (length > 0) {
	var id       = folder_id;
    var set      = top.setFolders;
    var prev_id  = -1;
    var i, val;
	for (i = 0; i < length; ++i) {
	  val = set[i];
	  if (val < id) {
        if (prev_id < val) {
		  prev_id = val;
    } } }
	if (prev_id != -1) {
  	  submitPost({ action:myScript,
                   parameters:{ folder_id:prev_id } } );
} } }

function sortNumber(a, b)
{
  return a - b;
}

function list_folders()
{
  var length = top.setFolders_lth;

  if (length > 0) {
	var setFolders_array  = top.setFolders;

    setFolders_array.sort(sortNumber);

    submitPost({ action:'listSetFolders.php',
                 parameters:{ folder_id:folder_id,
                              url:myScript,
                              set:'['+setFolders_array.toString()+']'
    } } );
  }
}

function showSetButtons()
{
  var length = top.setFolders_lth;

  if (length > 0) {
	var id  = folder_id;
    var set = top.setFolders;
    var i, button;
	for (i = 0; i < length; ++i) {
	  if (set[i] > id) {
		button = document.getElementById('next');
        button.style.display = '';
		break;
	} }
	for (i = 0; i < length; ++i) {
	  if (set[i] < id) {
		button = document.getElementById('prev');
        button.style.display = '';
		break;
	} }
    if (length > 1) {
      button = document.getElementById('list');
      button.style.display = '';
} } }
