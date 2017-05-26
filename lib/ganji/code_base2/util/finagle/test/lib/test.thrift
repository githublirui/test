namespace java  com.ganji.test.finagleservice.thrift
namespace php  test

struct  TestModel{
      1:i32 id;
      2:i32 num;
      3:string name;
}

service  TestService {

  string getName(1:string id)

}