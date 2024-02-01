function today(elt) {
  $(elt).val(new Date().toLocaleDateString(getLocale()));
  return false;
}


