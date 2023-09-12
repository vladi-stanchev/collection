<?php

function clean_test_string($string)
{
  return str_replace(["\n", "\r", ' '], '', $string);
}
