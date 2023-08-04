# Documentation Serivce
## Service to extend gcgov/framework

### Primary purpose
* Adds route /documentation.yaml to application that, when accessed, uses the zircote/swagger-php library to scan the \app and \gcgov\framework namespaces for open api documentation annotations and attributes and generates a comprehensive open api documentation yaml file that can be loaded into Swagger UI for integrated API documentation