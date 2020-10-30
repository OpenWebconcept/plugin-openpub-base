# Rest API

## Available endpoints

### Main endpoint

`https://url/wp-json/owc/openpub/v1`

### Endpoint of the openpub-items, active and inactive

`https://url/wp-json/owc/openpub/v1/items`

Parameters:

* `highlighted=true` Get all items that are marked as highlighted (optional)
* `highlighted=false` Get all items thar are marked as not highlighted (optional)
* `limit={int}` Limit the amount of items per page (default: 10)
* `page={int}` Get selected page of items (optional)

### Endpoint of the openpub-items, active only

`https://url/wp-json/owc/openpub/v1/items/active`

### Endpoint of the openpub-item detail page on id

`https://url/wp-json/owc/openpub/v1/items/{id}`

### Endpoint of the openpub-item detail page on slug

`https://url/wp-json/owc/openpub/v1/items/{slug}`

### Endpoint of the theme-items

`https://url/wp-json/owc/openpub/v1/theme`

### Endpoint of the theme detail page

`https://url/wp-json/owc/openpub/v1/themes/{id}`

### Endpoint of searching

`https://url/wp-json/owc/openpub/v1/search`