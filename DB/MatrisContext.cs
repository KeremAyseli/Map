using MongoDB.Driver;
using MapApi.Models;
namespace MapApi.DB
{
    public class MatrisContext
    {
        MongoClientSettings settings;
        IMongoDatabase database;
        private readonly IMongoCollection<MatrisDTO> _matris;
        public MatrisContext()
        {
            var settings = MongoClientSettings.FromConnectionString("mongodb+srv://admin:Q3o8w8V9kDVryLmp@cluster0.n8wz0.mongodb.net/React?retryWrites=true&w=majority");
            var client = new MongoClient(settings);
            var database = client.GetDatabase("IntegerSpiral");
            _matris = database.GetCollection<MatrisDTO>("layout");
        }
    }
}