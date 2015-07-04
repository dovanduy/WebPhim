function toggle(source) {
  checkboxes = document.getElementsByName('allCheck');
  for each(var checkbox in checkboxes)
    checkbox.checked = source.checked;
}