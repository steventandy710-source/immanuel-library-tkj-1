<?php

function validateBook($request): array
{
    $errors = [];

    // Validate title: required and max 100 characters
    if ($request['title'] == '') {
        $errors['title'] = "Judul buku wajib diisi";
    } elseif (mb_strlen($request['title']) > 100) {
        $errors['title'] = "Judul buku tidak boleh melebihi 100 karakter";
    }

    return $errors;
}