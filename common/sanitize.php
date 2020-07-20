<?php

// Helper utility to make user data safer to parse
function sanitize($str) {
    return strip_tags(stripslashes(htmlentities($str)));
}
