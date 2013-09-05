<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/users.php');

htmlHeader('Annotation Definition');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php
bodyHeader(''); 
?>
<h3>What is a Project?</h3>
<p>
Projects form the basis of a registered imageMAT user\'s catalogue of
created annotations. The user can set up projects to collect and share
a series of annotations about a particular image, repository, or larger body
of research.
<p>
Each registered user is provided a home directory folder, which behaves much
like a directory in a unix file system.  They may create subfolders under any
folder in or under this home directory folder, but no where else, and may
place annotations, and associated URLS's in these folders, and subject to
group permisssions in other peoples folders.
<p>
Users may also place links to arbitrary folders in any folder they have created,
permitting these other folders to logically behave as if they were subfolders
of this folder.
Such links permit users to readily navigate to other peoples subfolders,
and to advertise that other peoples subfolders are relevant to the
project contained within one of their own folders. Users may also be permitted
(subject to group permissions) to create (and modify) links in other peoples
folders.
<p>
Users may also create links directly under the root folder.  Such links
are visible only to the individual that created them, and thus behave
much like personal favourites.  They permit navigation by the individual
who created them, as do other links, but unlike other links then do not
convey information to other users.
<p>
Collaboration can be achieved in various ways.
<p>
(1). A trusted group of individuals
can share an existing project folder structure created by some 
administrator, and place annotations, urls, and additional links directly
into this this project folder structure.  Some members of this group might
be permitted to also directly manipulate the project folder structure
itself (acting as if they were the owner of this folder structure).  The
advantage of this approach is that all members see changes to the collective
project space.
The drawbacks of such an arrangement is that the owner of the project structure
(and potentially others) are at liberty to delete arbitrary content within
this structure, and it becomes potentially unclear who is doing what to the
collective information contained within the project space.
<p>
(2) A somewhat less trusting group of individuals can collaborate
by each permitting others in the group to link to their
work on a project, thus consolidating each individual works on a project
into larger project spaces separately managed and administered by interested
parties.
Such links establish one way (but potentially bilateral) relationships,
with (if all members of the group link to all other members subprojects) the
appearance of common multilateral collaboration between all members of the
group emerging from the individual bilateral reoaltionships.  The
advantage of this approach is that each member of a project group can decide
which other members of the group they wish to enjoy a working relationship with,
and can better manage access to their own research, without potential protest
from others in the group.  Disadvantages are that
users may be unaware of the total collective material identified as belonging
within a project, since there is no centralised place where this total
collective material is stored, and this total collective material may be
organised in very different ways by the individuals administering the various
parts of this collective information.
<p>
(3) An even more distant form of sharing can be achieved by users simply
migrating annotations and url's into their own personal space.  Such annotations
and url's have a single identity (unless duplicate copies of annotations are
created)
even when distributed into multiple folders.  An update to such an 
annotation in one folder, will be automatically visible (if published)
to all other folders that reference this same annotation.
<p>
(4) A final layer of collaboration exists in users potentially being able to
comment on, and rank annotations of interest to them.  Such comments may be
attached either to the annotation across projects hosting it, or be specific
to an annotation within a project, or potentially to an identifiable group
of individuals potentially related to a project.

<?php
done:
bodyFooter();
?>
</body>
</html>
