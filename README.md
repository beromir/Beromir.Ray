# Ray Helper for Neos CMS

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
You can download the app here: https://spatie.be/docs/ray/v1/the-ray-desktop-app/download-the-free-demo.

## Configuration
Create the configuration file `ray.php` in your Neos site root directory.  
Configuration options: https://spatie.be/docs/ray/v1/configuration/framework-agnostic-php.  
Configuration options when you use Docker: https://spatie.be/docs/ray/v1/environment-specific-configuration/docker.

## Usage
You can use all Ray functions from https://spatie.be/docs/ray/v1/usage/framework-agnostic-php-project.  
Reference list (just the PHP specific functions): https://spatie.be/docs/ray/v1/usage/reference.

To use the functions, you must add them as key-value pairs in Fusion.  
If the function does not require any parameters, you can use `null`, `false` or an empty string as the value.  
To pass parameters, add them as a value.

```html
valueToDebug = 'Show this text in the Ray app.'
valueToDebug.@process.debug = Beromir.Ray:Debug {
   // Show a label in the Ray app
   label = 'Text'
   // Colorize the output
   red = ''
   // Show the output as large text
   large = ''
}
```

```html
valueToDebug = 'Show this text in the Ray app.'
valueToDebug.@process.debug = Beromir.Ray:Debug {
   // Show a label in the Ray app
   label = 'Text'
   // Only send a payload once when in a loop
   once = ${node}
}
```

**Debug a Fusion expression:**
```html
valueToDebug = 'Show this text in the Ray app.'
valueToDebug.@process.debug = Beromir.Ray:Debug
```

Alternative ways:
```html
// Debug the current node
debug = Beromir.Ray:Debug {
   value = ${node}
}

renderer = afx`
   {props.debug}
`
```

```html
renderer = afx`
   <Beromir.Ray:Debug debugValues={node}/>
`
```

**Debug multiple values:**
```html
debug = Beromir.Ray:Debug {
   // Pass the values as an array
   value = ${[node, site]}
}

renderer = afx`
   {props.debug}
`
```

**Use Debug Actions to debug NodeTypes:**
```html
// Display the NodeType name of the node
debug = Beromir.Ray:Debug {
   value = ${node}
   debugAction = 'nodeTypeName'
}

renderer = afx`
   {props.debug}
`
```

```html
// Display the properties of the current node and the site node
debug = Beromir.Ray:Debug {
   value = ${[node, site]}
   debugAction = 'properties'
}

renderer = afx`
   {props.debug}
`
```

You can use the following Debug Actions for NodeTypes:

| Debug Action| Description |
| --- | --- |
| `nodeTypeName` | Display the NodeType name of a node |
| `context` | Display the context of a node |
| `contextPath` | Display the context path of a node |
| `properties` | Display the properties of a node |
