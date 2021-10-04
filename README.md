# Ray Package for Neos CMS

Debug your Neos site with the Ray app.  
Ray is a debugger app from Spatie. You can find more information about Ray on https://myray.app/.  
Official Ray docs: https://spatie.be/docs/ray/v1/introduction.

## Installation
Run the following command in your site package:
```
composer require --no-update beromir/neos-ray
```
Then run `composer update` in your project root.

## Requirements
You need to have the Ray app installed and a valid license.
You can download the app here: https://myray.app.

## Configuration
Create the configuration file `ray.php` in your Neos site root directory.  
Configuration options: https://spatie.be/docs/ray/v1/configuration/framework-agnostic-php.  
Configuration options when you use Docker: https://spatie.be/docs/ray/v1/environment-specific-configuration/docker.

## Usage
**Debug a Fusion expression:**
```html
valueToDebug = 'Show this text in the Ray app.'
valueToDebug.@process.debug = Beromir.Ray:Ray
```

Alternative ways:
```html
// Debug the current node
debug = Beromir.Ray:Ray {
   debugValues = ${node}
}

renderer = afx`
   {props.debug}
`
```

```html
renderer = afx`
   <Beromir.Ray:Ray debugValues={node}/>
`
```

**Debug multiple values:**
```html
debug = Beromir.Ray:Ray {
   // Pass the values as an array
   debugValues = ${[node, site]}
}

renderer = afx`
   {props.debug}
`
```

**Use Debug Actions:**
```html
// Display the NodeType name of the node
debug = Beromir.Ray:Ray {
   debugValues = ${node}
   debugAction = 'nodeTypeName'
}

renderer = afx`
   {props.debug}
`
```

```html
// Display the properties of the current node and the site node
debug = Beromir.Ray:Ray {
   debugValues = ${[node, site]}
   debugAction = 'properties'
}

renderer = afx`
   {props.debug}
`
```

You can use the following Debug Actions:

| Debug Action| Description |
| --- | --- |
| `phpInfo` | Display PHP info |
| `backtrace` | Display entire backtrace |
| `nodeTypeName` | Display the NodeType name of a node |
| `context` | Display the context of a node |
| `contextPath` | Display the context path of a node |
| `properties` | Display the properties of a node |
