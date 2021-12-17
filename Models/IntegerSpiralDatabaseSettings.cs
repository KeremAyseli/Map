namespace MapApi.Models{
    public class IntegerSpiralDatabaseSettings:IIntegerSpiralDatabaseSettings{
        public string CollectionName{get;set;}="layout";
        public string ConnectionString {get;set;}="mongodb+srv://admin:Q3o8w8V9kDVryLmp@cluster0.n8wz0.mongodb.net/IntegerSpiral?retryWrites=true&w=majority";
        public string DatabaseName {get;set;}="IntegerSpiral";
    }
    
}