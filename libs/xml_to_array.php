<?php

function xml_to_array(SimpleXMLElement $parent)
{
  // inisialisasi node array
  $array = array();

  // iterasi setiap node dalam parent
  foreach ($parent as $name => $element) {
    // cek apakah node sudah ada dalam array
    ($node = &$array[$name])
      // jika ada dan hanya satu, maka jadikan array
      && (1 === count($node) ? $node = array($node) : 1)
      // jika sudah array, maka tambahkan node baru
      && $node = &$node[count(value: $node)];

    // jika node memiliki atribut, maka tambahkan ke array
    // jika tidak punya atribut, simpan trim nilai node tersebut ke array 
    $node = $element->count() ? xml_to_array($element) : trim($element);
  }
  return $array;
}
