<?php
$students = \App\Models\User::where('role', 'etudiant')->get();
foreach($students as $student) {
    $parentEmail = $student->email . '+parent';
    $parent = \App\Models\User::where('email', $parentEmail)->first();
    if(!$parent) {
        $newParent = new \App\Models\User();
        $newParent->name = $student->name;
        $newParent->prenom = 'Parent';
        $newParent->email = $parentEmail;
        $newParent->role = 'parent';
        $newParent->is_active = $student->is_active;
        $newParent->email_verified_at = $student->email_verified_at;
        // set raw password to bypass double hashing
        $newParent->setRawAttributes([
            'password' => $student->password
        ], true);
        $newParent->save();
        echo "Created parent for " . $student->email . "\n";
    }
}
echo "Done.\n";
