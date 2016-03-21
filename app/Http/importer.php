<?php

function importCsv($fileName, $modelName, $fillable, $update = false) {
    $fileName = storage_path() . '/upload/' . $fileName;
    $delimiter = ',';
    $errors = [];

    if (!file_exists($fileName)) {
        $errors[] = $fileName . ' does not exist.';
        return $errors;
    }

    if (!is_readable($fileName)) {
        $errors[] = $fileName . ' must be csv file.';
        return $errors;
    }

    $data = [];

    if (($handle = fopen($fileName, 'r')) === false) {
        return false;
    } 

    /*
     * 初めの行はfillableな属性名
     */
    if (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
        for ($i = 0; $i < count($row); $i++) {
            if ($row[$i] !== $fillable[$i]) {
                $errors[] = 'The first line must be ' 
                    . implode($fillable, ', ') . '.';
                return $errors;
            }
        }
    }

    $data = [];
    $line = 2;
    $rules = config('validations.' . mb_strtolower($modelName) . 's');
    $uniqueKeys = []; /* validationにuniqueが含まれているkey */

    foreach ($rules as $key => $rule) {
        if (preg_match('/.*unique.*/', $rule)) {
            $uniqueKeys[] = $key;
        }
        if ($update) {
            $rules[$key] = preg_replace('/\|unique[^\|]*/', '', $rule);
        }
    }

    /* 
     * validation 
     */
    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
        $new = array_combine($fillable, $row);
        $canInsert = true;

        $validator = Validator::make($new, $rules);
        if ($validator->fails()) {
            $errors[] = $validator->errors()->first() . " (on line $line.)";
            $canInsert = false;
        }

        if ($update) {
            foreach ($uniqueKeys as $u) {
                foreach ($data as $d) {
                    if ($d[$u] === $new[$u]) {
                        $errors[] = 
                            "The $u ($new[$u]) has already been taken.  (on line $line.)";
                        $canInsert = false;
                    }
                }
            }
        }
        if ($canInsert) {
            $data[] = $new;
        }
        $line++;
    }

    fclose($handle);

    /*
     * Insert
     */
    $modelName = 'App\\Http\\Models\\' . $modelName;
    if ($update) {
        foreach ($data as $d) {
            $model = new $modelName($d);
            $id = $modelName::where('name', $d['name'])->firstOrFail()->id;
            $model = $model->findOrFail($id);
            $model->update($d);
        }
    } else {
        foreach ($data as $d) {
            $model = new $modelName($d);
            $model->save();
        }
    }

    return $errors;
}
