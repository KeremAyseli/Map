using MapApi.Models;
using MapApi.DB;
using MongoDB.Driver;
namespace MapApi.Services
{
    public class SpiralServices : ISpiralServices
    {
        private readonly IMongoCollection<MatrisDTO> _matris;
        private MatrisService _MatrisService; 
        public SpiralServices(IIntegerSpiralDatabaseSettings settings){
            Console.WriteLine(settings.ConnectionString);
             var client =new MongoClient(settings.ConnectionString);
            var database = client.GetDatabase(settings.DatabaseName);
            _matris=database.GetCollection<MatrisDTO>(settings.CollectionName);
            
        }
        public MatrisDTO GetLayouts(int x, int y, string layoutId)
        {
           return _matris.Find<MatrisDTO>(matris=>matris.Id==layoutId).FirstOrDefault();
        }

        public List<MatrisDTO> GetLayouts()
        {
           return _matris.Find(matris=>true).ToList<MatrisDTO>();
        }

        public int GetValueOfLayout(string layoutId, int x, int y)
        {
            throw new NotImplementedException();
        }

        public void InsertLayout(int x, int y)
        {
            _MatrisService=new MatrisService(x,y);
            _matris.InsertOne()
        }
    }
}