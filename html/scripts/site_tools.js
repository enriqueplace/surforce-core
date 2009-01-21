function var_dump(obj) {
    if(typeof obj == "object") {
       return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
    } else {
        return "Type: "+typeof(obj)+"\nValue: "+obj;
    }
}